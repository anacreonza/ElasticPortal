# Web portal to Media24's ElasticSearch archive.

A portal site, designed to give people access the Media24's ElasticSearch archive without needing the desktop software to be installed. It shows panes for 3 types of content: images, stories (XML files from EidosMedia Methodé) and PDFs (printed pages).

## Done:
* Search terms are now derived from an aggregation search (types, publications and categories).
* The aggs results are stored in the browser session to speed up loading of the search form.
* Date range searches work - although there is no validation.
* Internal IP addresses are stored in elastic.php config file - which is not included in this repo.
* Reskinned the entire site with a more modern look.
* Added Laravel authentication. User DB is stored in an SQLite DB stored at /database/database.sqlite


## To Do:

* Suggesters (needs reactive page)
* Add author filter.
* Add Adldap2 extension for auth.
* Seperate out metadata key lists to allow site to be resused more easily.
* Proper JS masonry-style multi-image viewer.
* Squeeze more text out of stories that have oddly formatted content
