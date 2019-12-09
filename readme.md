# Web portal to Media24's ElasticSearch archive.

A portal site, designed to give people access the Media24's ElasticSearch archive without needing the desktop software to be installed. It shows panes for 3 types of content: images, stories (XML files from EidosMedia Methodé) and other documents (like PDFs or html pages).

## Done:
* Searches are done on smaller sets to increase speed.
* The aggs results are stored in a 12 hour cookie to speed up loading of the search form.
* Reskinned the entire site with a more modern look.
* Added back handling legacy ASPSeek index.
* Improvements to story preview page - these still need to be copied over to images etc.

## To Do:

* Suggesters (needs reactive page)
* Add author filter.
* Add Adldap2 extension for auth.
* Proper JS masonry-style multi-image viewer.
