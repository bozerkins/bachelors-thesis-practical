#!/usr/bin/env bash

sqoop export \
  --connect jdbc:mysql://localhost:3306/bachelors_etl  \
  --username root \
  --table final_visits \
  --export-dir $BACHELORS_PROJECT_DIR/bin/data_io/visits_today \

sqoop export \
  --connect jdbc:mysql://localhost:3306/bachelors_etl  \
  --username root \
  --table final_actions \
  --export-dir $BACHELORS_PROJECT_DIR/bin/data_io/actions_today \


