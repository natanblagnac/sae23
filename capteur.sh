#!/bin/bash

# --- Configuration ---
# Your MQTT topics, one for each room's sensor data.
MQTT_TOPICS=(
    "AM107/by-room/B106/data"
    "AM107/by-room/B110/data"
    "AM107/by-room/E003/data"
    "AM107/by-room/E104/data"
)

# MQTT Broker connection details
MQTT_BROKER="mqtt.iut-blagnac.fr"
MQTT_PORT="1883"

# MySQL Database connection details
DB_USER="viger"
DB_PASS="natan"
DB_NAME="sae23" # Ensure this matches your database name

# --- Main Script Logic ---

echo "Starting MQTT subscriptions for sensor data..."
echo "Connecting to $MQTT_BROKER:$MQTT_PORT"

# Loop through each topic and start a background process for subscription.
# Each process will read messages and pipe them to the processing function.
for TOPIC in "${MQTT_TOPICS[@]}"; do
    mosquitto_sub -h "$MQTT_BROKER" -p "$MQTT_PORT" -t "$TOPIC" | while read -r PAYLOAD; do
        # Extract data using jq
        TEMP=$(echo "$PAYLOAD" | jq '.[0].temperature')
        HUMID=$(echo "$PAYLOAD" | jq '.[0].humidity')
        CO2=$(echo "$PAYLOAD" | jq '.[0].co2')
        ROOM=$(echo "$PAYLOAD" | jq -r '.[1].room') # -r for raw string output

        DATETIME=$(date "+%Y-%m-%d %H:%M:%S")

        # Define sensor types and their units for easy iteration
        declare -A SENSORS=(
            ["temperature"]="$TEMP"
            ["humidity"]="$HUMID"
            ["co2"]="$CO2"
        )
        declare -A UNITS=(
            ["temperature"]="°C"
            ["humidity"]="%"
            ["co2"]="ppm"
        )

        echo "Received message for room '$ROOM' at $DATETIME"

        # Process each sensor data point (temperature, humidity, co2)
        for TYPE in "${!SENSORS[@]}"; do
            SENSOR_NAME="${TYPE}_${ROOM}"
            VALUE="${SENSORS[$TYPE]}"
            UNIT="${UNITS[$TYPE]}"

            # Skip if value is empty or "null"
            if [[ -z "$VALUE" || "$VALUE" == "null" ]]; then
                echo "  Warning: '$TYPE' value for room '$ROOM' is missing or null. Skipping."
                continue
            fi

            # --- Database Insertion ---
            # 1. Insert/Update Capteur (sensor definition)
            #    INSERT IGNORE prevents errors if the sensor already exists.
            mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "
                INSERT IGNORE INTO Capteur (nom_capteur, type_capteur, Unite, nom_salle)
                VALUES ('$SENSOR_NAME', '$TYPE', '$UNIT', '$ROOM');
            "
            if [ $? -eq 0 ]; then
                echo "  Capteur '$SENSOR_NAME' (Type: $TYPE, Room: $ROOM) handled."
            else
                echo "  Error handling Capteur '$SENSOR_NAME'."
            fi

            # 2. Insert Mesure (actual sensor reading)
            mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "
                INSERT INTO Mesure (nom_capteur, valeur, date_heure)
                VALUES ('$SENSOR_NAME', '$VALUE', '$DATETIME');
            "
            if [ $? -eq 0 ]; then
                echo "  Mesure inserted for '$SENSOR_NAME': Value $VALUE at $DATETIME."
            else
                echo "  Error inserting Mesure for '$SENSOR_NAME'."
            fi
        done
        echo "---" # Separator for readability
    done & # Run this mosquitto_sub and its processing in the background
done

echo "All MQTT subscriptions are active. Press Ctrl+C to stop the script."
wait # Keep the main script running to allow background jobs to continue
