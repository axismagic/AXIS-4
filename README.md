AXIS
====

AXIS stands for Another XML Invoicing System and is, you guessed it, a XML-based invoicing system built with PHP, on top of Twitter Bootstrap.

Features
--------

* Generates ready-to-present estimates, for client approval
* Multi-language support (English and Portuguese languages included)
* Low-fidelity encryption for each individual estimate
* Direct client feedback, from the estimate page
* Multi-currency support, with immediate conversion

Installation
------------

AXIS does not rely on a database, and does not yet have an administration backend, so installation and management is all done by hand.

It works by creating and manipulating XML files inside the /data/ folder, then pointing the browser to index.php?id=1 (replace '1' with XML filename) to call the script, with data being fetched from its respective XML. An example file, named 1.xml, has been included for easy reference. Not all fields need to be included. Step-by-step instructions on how to edit these files will be made available somewtime in the near future.

Development Roadmap
-------------------

Although further development is expected, as of now there is no plan or set deadline to continue working on this. I've built this script for my own personal use, and expect to build on it, and tweak it as necessary, but will not attempt to make it user-friendly unless there is some interest from both end-users and developers.

If you are interested in helping out developing, or would find a use for a finer-tuned version of this, please [let me know](http://www.eduardonunes.me/person.php). Thank you.
