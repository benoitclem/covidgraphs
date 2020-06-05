#!/bin/sh


wget "https://github.com/CSSEGISandData/COVID-19/raw/master/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_deaths_global.csv"

wget "https://github.com/CSSEGISandData/COVID-19/raw/master/csse_covid_19_data/UID_ISO_FIPS_LookUp_Table.csv"

mv time_series_covid19_deaths_global.csv.1 time_series_covid19_deaths_global.csv;
mv UID_ISO_FIPS_LookUp_Table.csv.1 UID_ISO_FIPS_LookUp_Table.csv

dos2unix time_series_covid19_deaths_global.csv
dos2unix UID_ISO_FIPS_LookUp_Table.csv

awk -F',' 'BEGIN{print "data:[";};/Italy|^,France|Germany|US|Spain|^,United Kingdom|Portugal|Belgium|Luxembourg/{indice="0";print "{type:\"line\",name:\"" $2 "\",showInLegend:true,dataPoints:[";for(i=5;i<=NF;i++){if($i>=5){print "{label: \"" $2 "\",\"x\":" indice ",\"y\":" $i "},";indice++;}};print "]},";};END{print "]";}' time_series_covid19_deaths_global.csv > results.json


sed -i 's/"\(Korea\), \(.*\)"/\2 \1/' time_series_covid19_deaths_global.csv

awk -F',' 'BEGIN{print "data:[";getline};{indice="0";if($1!=""){print "{type:\"line\",name:\"" $2 "/" $1 "\",showInLegend:true,dataPoints:[";}else{print "{type:\"line\",name:\"" $2 "\",showInLegend:true,dataPoints:[";};for(i=5;i<=NF;i++){if($i>=5){print "{label: \"" $2 "/" $1 "\",\"x\":" indice ",\"y\":" $i "},";indice++;}};print "]},";};END{print "]";}' time_series_covid19_deaths_global.csv > results_all.json

awk -F',' 'BEGIN{print "data:[";getline;};FNR==NR{a["," $7 "," $8 ","]=$NF;next}{getline;ratio=$NF/a["," $1 "," $2 ","]*100;if($1!=""){name=$2 "/" $1;}else{name=$2;};print "{type:\"area\",name:\"" name "\",showInLegend:true,dataPoints:[{label:\"" name "\",y:" ratio "},]},";};END{print "]";}' UID_ISO_FIPS_LookUp_Table.csv time_series_covid19_deaths_global.csv > ratio_all.json

sed -i '/^.*y:inf,.*$/d' ratio_all.json
sed -i '/^.*y:-nan,.*$/d' ratio_all.json
sed -i '/^.*y:0,.*$/d' ratio_all.json
