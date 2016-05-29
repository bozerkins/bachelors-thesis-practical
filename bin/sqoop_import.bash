#!/usr/bin/env bash

rm -rf $BACHELORS_PROJECT_DIR/bin/data_io/piwik_visits_import

sqoop import \
  --connect jdbc:mysql://localhost:3306/bachelors_piwik  \
  --username root \
  --split-by idvisit \
  --as-textfile \
  --target-dir $BACHELORS_PROJECT_DIR/bin/data_io/piwik_visits_import \
  --query 'SELECT idvisit, HEX(idvisitor), visit_first_action_time FROM piwik_log_visit WHERE $CONDITIONS' \
  --boundary-query 'select min(idvisit), max(idvisit) from piwik_log_visit' \


rm -rf $BACHELORS_PROJECT_DIR/bin/data_io/piwik_actions_import

sqoop import \
  --connect jdbc:mysql://localhost:3306/bachelors_piwik  \
  --username root \
  --split-by idlink_va \
  --as-textfile \
  --target-dir $BACHELORS_PROJECT_DIR/bin/data_io/piwik_actions_import \
  --query 'SELECT idlink_va, HEX(idvisitor), time_spent_ref_action, server_time FROM piwik_log_link_visit_action WHERE $CONDITIONS' \
  --boundary-query 'select min(idlink_va), max(idlink_va) from piwik_log_link_visit_action' \