.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _introduction:

Introduction
------------

In TypoScript templates there exists a function called :ref:`getText <t3tsref:data-type-gettext>`,
which makes it possible to retrieve values from a great number of
sources (GET/POST, TSFE, rootline, etc.). This extension provides a
library which aims to reproduce this capability, so that it can be
used by developers in their own extension.

This can be useful in cases where you have some kind of configuration
but are not using TypoScript, for whatever reason.


.. _expression:

So what is an expression?
^^^^^^^^^^^^^^^^^^^^^^^^^

Like for the TypoScript function, an expression – at a minimum – is
comprised of a key, a colon (:) and a value. Example:

.. code:: text

	gp:no_cache

The above syntax will instruct the parser to look for a GET/POST
variable called "no\_cache". The key represents the "space" where to
look for the value. With some special keys, the value takes a
different meaning. This is documented below.

The parser library can either parse a single expression or parse
expressions inside strings, provided expressions are placed between
curly braces. Example:

.. code:: text

	This is the value of no_cache: {gp:no_cache}


.. _introduction-questions:

Questions?
^^^^^^^^^^

If you have any questions about this extension, please ask them in the
TYPO3 English mailing list (typo3.english), so that others can benefit
from the answers.


.. _introduction-happy-developer:

Keeping the developer happy
^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you like this extension, use the social functions of the TER to
make some buzz about it. Alternatively, if you want to give something
back, you may consider my Amazon wish list:
http://www.amazon.co.uk/registry/wishlist/G7DI2AN99Y4F

You may also take a step back and reflect about the beauty of sharing.
Think about how much you are benefiting and how much yourself is
giving back to the community.
