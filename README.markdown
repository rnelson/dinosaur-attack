Where the #@&*'s My Bus?
========================
This is a temporary, quick hack version of WTFMB.

A newer, shinier, better, less crashy version with more features is slowly being worked on. It will be in a [TractorFeed][tf] repo and we'll publicly announce it on [our twitter][tftwitter]. Until that point, [the live copy][buspretendamazing] is pulled directly from this repo on [my GitHub account][rnelsongh].

Some planned improvements:

+ Complete rewrite in Ruby, no more crashing node.js server (poor programming on our part; we really just want to get away from running a server for it)
+ A better icon for the bus
+ Direction support, rotating the bus icon to match the bus direction
+ Ability to zoom in and out on maps (JS if possible, else URL parameters)
+ Dynamic list of known routes; there is currently a static list of supported routes on the page, but we don't want this to be [#LNK][poundlnk]-specific.

[tf]: https://github.com/tractorfeed
[tftwitter]: http://twitter.com/tractorfeedorg
[rnelsongh]: http://github.com/rnelson
[poundlnk]: http://twitter.com/search/%23LNK
[buspretendamazing]: http://bus.pretendamazing.org

