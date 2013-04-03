# Code to check that CSV is well formatted - had some issues in the past. Highlights the line number which needs fixing.
# Authors Robert Walport and Rashmi Raman

import csv


file1reader = csv.reader(open("formatted_data.csv"), delimiter=",")

header1 = file1reader.next() #header

lineno = 1
for values in file1reader:
	try:
		lineno += 1
		RESTAURANT,STREET_ADDRESS,CITY_ZIP,COMMENTS = values
	except:
		print "Problem at line #",lineno
		print repr(values)
		break    
    