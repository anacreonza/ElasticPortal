# Web portal to Media24's ElasticSearch archive.

A portal site, designed to give people access the Media24's ElasticSearch archive without needing the desktop software to be installed. It shows 4 different panes for 4 types of content: images, stories (XML files from EidosMedia Methodé), PDFs (printed pages) and HTML files - which are mostly maps of page layouts but also sometimes older archived web stories.

## Done:
* Search terms are now derived from an aggregation search (types, publications and categories).
* The aggs results are stored in the browser session to speed up loading of the search form.
* Date range searches work - although there is no validation.
* Internal IP addresses are stored in elastic.php config file - which is not included in this repo.

## To Do:

* Suggesters (needs reactive page)
* Add author filter.
* Validate date ranges.
* Use the internal pagination function of ES instead of my own.
* Add Adldap2 extension for auth.
* Give the app its own local DB (sqlite to allow portability?)
