#!/usr/bin/env bash

rm -rf $BACHELORS_PROJECT_DIR/bin/data_io/actions_today

hadoop \
jar $HADOOP_INSTALL/share/hadoop/tools/lib/hadoop-streaming-2.7.2.jar \
-mapper $BACHELORS_PROJECT_DIR/bin/scripts/mapper_actions.php \
-reducer $BACHELORS_PROJECT_DIR/bin/scripts/reducer_actions.php \
-input $BACHELORS_PROJECT_DIR/bin/data_io/piwik_actions_import/part-m-* \
-output $BACHELORS_PROJECT_DIR/bin/data_io/actions_today \
-verbose
