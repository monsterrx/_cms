#!/bin/bash

# Start cron
service cron start

# Start apache (in foreground to keep the container alive)
apache2-foreground
