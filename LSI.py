#Code to produce LSI topics
# Authors Robert Walport and Rashmi Raman

from gensim import corpora, models, similarities
import logging
logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)

dictionary = corpora.Dictionary.load('restaurantreviews.dict')
corpus = corpora.MmCorpus('reviewscorpus.mm')
print corpus

tfidf = models.TfidfModel(corpus)

corpus_tfidf = tfidf[corpus]
lsi = models.LsiModel(corpus_tfidf, id2word=dictionary, num_topics=10)
corpus_lsi = lsi[corpus_tfidf]
test = lsi.print_topics(10)
for entry in test:
    print entry + "\n"

restaurantslist = []
for line in open('restaurants.txt'):
    restaurantslist.append(line)
    
i = 0
totals = [0,0,0,0,0,0,0,0,0,0,0]
for entry in corpus_lsi:
    biggesttopic = 0
    topic = 11
    for subentry in entry:
        if subentry[1] > biggesttopic:
            topic = subentry[0]
            biggesttopic = subentry[1]
    totals[topic] += 1
    print restaurantslist[i] + " " + str(topic) + " " + str(biggesttopic)
    i += 1
print totals

lda = models.ldamodel.LdaModel(corpus_tfidf, id2word=dictionary, num_topics=10)
corpus_lda = lda[corpus_tfidf]
print corpus_lda.print_topics(10)
