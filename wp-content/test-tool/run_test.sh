#!/bin/bash

if [ $# -gt 0 ]; then
	PROBLEM="$1";
else
	PROBLEM='sum';
fi

source ./config.sh "$PROBLEM";
NUMTEST=`ls $input_dir/*.in | wc -l`;

function updateDB () {
	user="$1";
	prob="$PROBLEM";
	score="$3";
	detail="$4";
	
	query="UPDATE wp_submit SET score=${score}, score_detail='${detail}', status='done' WHERE status='pending' AND user_name='${user}' AND prob_name='${prob}' AND deleted=0";
	echo "$query";
	echo "$query" | mysql -h$MYSQL_HOST -u$MYSQL_USER -p$MYSQL_PASS $MYSQL_DB;
	return $?;
}

function markProb () {
	if [ $# -ne 2 ]; then return 0; fi;
	
	cd ./tmp;	
	cp "$1" -f -t ./;
	
	score=0;
	detail=''
	for (( i = 1; i <= $NUMTEST; i++ )); do 
		rm ./$PROBLEM.out ./$PROBLEM.in ./$PROBLEM.correct -f;
		
		cp "$input_dir/$i.in" -f "./$PROBLEM.in"
		cp "$output_dir/$i.out" -f "./$PROBLEM.correct"
		
		php -f $PROBLEM.txt >> "$log_file" 2>&1 &
		pid=$!;
		
		sleep $time_out;
		if [ `ps -p $pid | wc -l` -eq 2 ]; then
			echo -e "\ttest $i TIME OUT" 2>&1 | tee "$log_file";
			detail="${detail}t";
			res="-1";
			kill -9 "$pid" > /dev/null 2>&1;
		else 
			php -f $PROBLEM.txt >> "$log_file" 2>&1;
			res=$?;
		fi
				
		if [ "$res" -eq 0 -a -f "$PROBLEM.out" ]; then
			diff ./$PROBLEM.out ./$PROBLEM.correct >> "$log_file" 2>&1
			if [ $? -eq 0 ]; then 
				echo -e "\ttest $i OK" 2>&1 | tee "$log_file" ;
				score=$(($score + 1)); 
				detail=$detail"*";
			else 
				detail=$detail"x";
				echo -e "\ttest $i WRONG" 2>&1 | tee "$log_file";
			fi
		elif [ $res -ne -1 ]; then
			echo -e "\ttest $i ERROR" 2>&1 | tee "$log_file";
			detail="${detail}s";
		fi;
	done;
	
	echo "SCORE: ${score} ${detail}" 2>&1 | tee "$log_file";
	
	cd - > /dev/null;
	updateDB "$2" "$1" "$score" "$detail";
	if [ $? -ne 0 ]; then
		echo "ERROR: can not update to database" 2>&1 | tee "$log_file";
		return 1;
	else 
		echo "UPDATE to database successfully" 2>&1 | tee "$log_file"; 
	fi
	
	return 0;
}

rm -Rf ./tmp;
mkdir ./tmp;
[ -d `dirname "$log_file"` ] || mkdir -p `dirname "$log_file"`;

for user in `ls "$USER_PATH"`; do
	user_dir="$USER_PATH/$user";
	prob="$user_dir/${PROBLEM}.txt";
	
	echo "***** user:$user *****" 2>&1 | tee "$log_file";

	if [ ! -d "$user_dir" -o ! -f "$prob" ]; then continue; fi;
	
	if [ -f "$prob" ]; then 
		markProb "$prob" "$user";
	fi
done;

