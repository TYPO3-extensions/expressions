.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _user-functions:

Function keys reference
^^^^^^^^^^^^^^^^^^^^^^^

It is possible to create processors for custom function keys (see
"Developer's Guide") and thus be able to call pretty much any function
inside an expression.

- `boolean`_
- `floatval`_
- `fullQuoteStr`_
- `hsc`_
- `intval`_
- `quoteStr`_
- `removeXSS`_
- `strip\_tags`_
- `strftime`_


.. _user-functions-fullquotestr:

fullQuoteStr
""""""""""""

Calls :code:`\TYPO3\CMS\Core\Database\DatabaseConnection::fullQuoteStr()`.

Requires an additional argument, which corresponds to a table name
(see the method's comments if in doubt).

Will not be available if the :code:`$TYPO3_DB` global variable is not set.

**Example**

Call :code:`\TYPO3\CMS\Core\Database\DatabaseConnection::fullQuoteStr()` on the value,
with "pages" as a second argument:

.. code:: text

	gp:tx_myext_pi1|foo->fullQuoteStr:pages


.. _user-functions-quotestr:

quoteStr
""""""""

Calls :code:`\TYPO3\CMS\Core\Database\DatabaseConnection::quoteStr()`.

Requires an additional argument, which corresponds to a table name
(see the method's comments if in doubt).

Will not be available if the :code:`$TYPO3_DB` global variable is not set.

**Example**

Call :code:`\TYPO3\CMS\Core\Database\DatabaseConnection::quoteStr()` on the value,
with "pages" as a second argument:

.. code:: text

	gp:tx_myext_pi1|foo->quoteStr:pages


.. _user-functions-striptags:

strip\_tags
"""""""""""

Calls the PHP function :code:`strip_tags()`. The additional argument
is optional and corresponds to the list of tags to preserve
(refer to the `PHP manual for strip_tags() <http://php.net/strip_tags>`_ for more details).

**Example**

Call the PHP function :code:`strip_tags()` on the value
but preserve :code:`<p>` tags:

.. code:: text

	gp:tx_myext_pi1|foo->strip_tags:<p>


.. _user-functions-removexss:

removeXSS
"""""""""

Calls :code:`\TYPO3\CMS\Core\Utility\GeneralUtility::removeXSS`.

**Example**

.. code:: text

	gp:tx\_myext\_pi1\|foo->removeXSS


.. _user-functions-intval:

intval
""""""

Calls the PHP function :code:`intval()`. The additional argument is
optional and corresponds to the base to use for conversion
(refer to the `PHP manual for intval() <http://php.net/intval>`_ for more details).

**Example**

Call the PHP function :code:`intval()` using base 8:

.. code:: text

	gp:tx\_myext\_pi1\|foo->intval:8


.. _user-functions-floatval:

floatval
""""""""

Calls the PHP function :code:`floatval()`
(refer to the `PHP manual <http://php.net/floatval>`_ for more details).

**Example**

.. code:: text

	gp:tx_myext_pi1|foo->floatval


.. _user-functions-boolean:

boolean
"""""""

This is an internal function. It's actually the opposite of the PHP
function :code:`empty()`. This means that any value of 0 or "0", or
an empty string will return :code:`FALSE`. Any other value will return :code:`TRUE`.

**Example**

Return FALSE if value is empty, TRUE otherwise:

.. code:: text

	gp:tx_myext_pi1|foo->boolean


.. _user-functions-hsc:

hsc
"""

Calls the PHP function :code:`htmlspecialchars()`. The additional
arguments are all optional and correspond respectively to the
behavior to adopt regarding single and double quotes, the character
set to use in the conversion and what to do about existing HTML
entities (refer to the `PHP manual for htmlspecialchars() <http://php.net/htmlspecialchars>`_ for more details).

**Example**

Call the PHP function htmlspecialchars(), without any additional arguments:

.. code:: text

	gp:tx_myext_pi1|foo->hsc


.. _user-functions-strftime:

strftime
""""""""

Calls the PHP function :code:`strftime()` . It expects a Unix
timestamp as a value and takes a date format as an additional argument
(refer to the `PHP manual for strftime() <http://php.net/strftime>`_ for more details).

**Example**

Call the PHP function strftime() to format the timestamp received:

.. code:: text

	gp:date->strftime:%d.%m.%Y
