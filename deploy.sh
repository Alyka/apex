#!/bin/bash

# Copy the desired file after the clone is completed
cp .env.example .env

docker compose up --build -d
