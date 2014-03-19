USER_PATH=`readlink -f /home/keq9/src/php/user/`
TESTCASE_PATH=`readlink -f /home/keq9/src/php/testcases/`
LOG_PATH=`readlink -f /home/keq9/src/php/log/`

PROBLEM="$1";
prob_dir=$TESTCASE_PATH/$PROBLEM;
input_dir="${prob_dir}/input";
output_dir="${prob_dir}/output";
time_out=3;

MYSQL_HOST='localhost';
MYSQL_USER='root';
MYSQL_PASS='abc123';
MYSQL_DB='wp';

time_now=`date +%m-%d-%y-%H-%M-%S`;
log_file=$LOG_PATH/$1-$time_now.log;

