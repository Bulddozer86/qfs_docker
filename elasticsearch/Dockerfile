FROM elasticsearch:5.3.0

RUN apt update \
    && cd /usr/share/elasticsearch/ \
    && ./bin/elasticsearch-plugin install https://github.com/vhyza/elasticsearch-analysis-lemmagen/releases/download/v5.3.0/elasticsearch-analysis-lemmagen-5.3.0-plugin.zip