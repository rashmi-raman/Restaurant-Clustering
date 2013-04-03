# Get the restuarant grade for the restaurants in the list collected from the Yelp API by searching the restaurant inspection dataset from NYC.gov
# Authors Robert Walport and Rashmi Raman


import csv


file1reader = csv.reader(open("formatted_data.csv"), delimiter=",")
file2reader = csv.reader(open("nyc_data.csv"), delimiter=",")
file3writer = csv.writer(open("formatted_data_with_grade.csv","wb"),delimiter=",")

header1 = file1reader.next() #header
header2 = file2reader.next() #header

  
# The most efficient way to do this was to build a dictionary with the names of the restaurants as keys. Avoids nested for loops and improves performance.
mydict = {rows[0]:"" for rows in file1reader}
print mydict

for CAMIS,DBA,BORO,BUILDING,STREET,ZIPCODE,PHONE,CUISINECODE,INSPDATE,ACTION,VIOLCODE,SCORE,CURRENTGRADE,GRADEDATE,RECORDDATE in file2reader:
        if DBA in mydict:
        	mydict[DBA] = CURRENTGRADE
        
for key, value in mydict.items():
   if value != "":
	   file3writer.writerow([key, value])
   else:
   	   file3writer.writerow([key,"A"])		#default grade since this restaurant was not present in the NYC dataset i.e. did not have any safety violations
    