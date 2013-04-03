# Code to format our imput data, outputting a clean restaurant list, dictionary and corpus with foodwords also removed.
# Authors Robert Walport and Rashmi Raman

import csv, sys, re
from gensim import corpora, models, similarities

with open(sys.argv[1], "rb") as csvFile:
    rawlist = []
    cleanlist = []
    finallist = []
    reader = csv.reader(csvFile, delimiter=',')
    for row in reader:
        rawlist.append(row)
    rawlist.pop(0)
    for entry in rawlist:
        cleanlist.append([entry[0], ''])
    for entry in cleanlist:
        if entry not in finallist:
            finallist.append(entry)
    for entry in finallist:
        for rest in rawlist:
            if entry[0] == rest[0]:
                try:
                    entry[1] = entry[1] + " " + rest[3]
                except IndexError:
                    entry[1] = entry[1]
    restaurants = open('restaurants.txt', 'w+')
    ratings = open('reviews.txt', 'w+')
    for entry in finallist:
        restaurants.write(entry[0].lower() + "\n")
        ratings.write(entry[1].lower() + '\n')
    restaurants.close()
    ratings.close()

stopwords = open('stopwords.txt', 'r').read().split()
foodwords = open('foodwords.txt', 'r').read().split()

class MyCorpus(object):
    def __iter__(self):
        for line in open('reviews.txt'):
            line = line.lower()
            scrubwords = re.findall(r'\w+', line)
            yield dictionary.doc2bow(scrubwords)

# collect statistics about all tokens
dictionary = corpora.Dictionary(line.lower().split() for line in open('reviews.txt'))
# remove stop words and words that appear only once
stop_ids = [dictionary.token2id[stopword] for stopword in stopwords if stopword in dictionary.token2id]
food_ids = [dictionary.token2id[foodword] for foodword in foodwords if foodword in dictionary.token2id]
once_ids = [tokenid for tokenid, docfreq in dictionary.dfs.iteritems() if docfreq == 1]
dictionary.filter_tokens(stop_ids + food_ids + once_ids) # remove stop words and words that appear only once
dictionary.compactify() # remove gaps in id sequence after words that were removed
dictionary.save('restaurantreviews.dict')


corpus_memory_friendly = MyCorpus() # doesn't load the corpus into memory!

corpora.MmCorpus.serialize('reviewscorpus.mm', corpus_memory_friendly)
