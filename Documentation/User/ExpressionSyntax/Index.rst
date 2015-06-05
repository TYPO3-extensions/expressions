.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _user-syntax:

Expression syntax
^^^^^^^^^^^^^^^^^

The general syntax of an expression – at its simplest – is the
following:

.. code:: text

	key:value1|value2|value3...

The key defines the "domain" where to look for the value or sequence
of values. For example, the key "page" means that values should be
looked for in the current page record.

The values can be multiple, separated by pipe symbols (\|). Each value
normally represents a "dimension" of the "domain" where the value is
being looked up. This can be either a dimension of an array or a
member variable of an object. This is totally interchangeable, i.e.
the expression parser will traverse the structure transparently
whether any given "dimension" is an actual array dimension or some
object property. Example:

.. code:: text

	tsfe:fe_user|user|uid

"fe\_user" is an object and "user" is one of its member variables. But
"user" is an array and "uid" is one its keys. As you can see in terms
of expression syntax, this is totally transparent.

If no key is found inside the string to parse, it is returned as is.

:ref:`Available keys are described below <user-keys>`.


.. _user-syntax-extended:

Extended syntax
"""""""""""""""

Additions to the base syntax make it possible to call functions for
post-processing the result of the expression. The syntax becomes:

.. code:: text

	key:value1|value2|value3...->function:arg1,arg2,...

The function call is indicated by the "->" symbol. Then comes the
function key, which the parser will match to an actual function or
method call. The function will receive the value of the expression as
an argument. It may also require additional arguments, which are
defined after a colon (:) and separated by commas (,). Example:

.. code:: text

   gp:tx_myext_pi1|foo->fullQuoteStr:pages

In this example, we retrieve the value of the GET/POST variable
:code:`tx_myext_pi1[foo]` and put it through
:code:`\TYPO3\CMS\Core\Database\DatabaseConnection::fullQuoteStr()`
with the "pages" table name as a second argument (the first one being the value itself).

Functions can be chained:

.. code:: text

	key:value1|value2|value3...->function1:arg1,arg2,...->function2:arg1,arg2,...->...

The call order is from left to right.

:ref:`Available functions are described below <user-functions>`.


.. _user-syntax-subexpressions:

Subexpressions
""""""""""""""

An expression could itself contain expressions. These are called
"subexpressions". The syntax might look something like:

.. code:: text

	tsfe:fe_user|user|{gp:fe_field}

In this case, the expression would first be parsed for subexpressions.
Assuming the GET/POST var "fe\_field" contained "name" as a value, the
expression would become

.. code:: text

	tsfe:fe_user|user|name

which would then be parsed normally.

Every feature of expressions can be used inside subexpressions, except
further subexpressions.


.. _user-syntax-alternative:

Alternative expressions
"""""""""""""""""""""""

An expression can be made of several expressions, separated by a
double slash (//):

.. code:: text

	key1:value1|value2|... // key2:value1|value2|... // ...

These represent alternatives. Expressions are evaluated from left to
right and the first one to return a value will end the process. Thus
the expression with "key2" (in the above example) will be evaluated
only if the first one didn't return anything.

This can be used in particular to define default values, by using an
alternate that is **not** an expression. Example:

.. code:: text

	gp:year // 2010

In this case, the expression parser will look for a GET/POST variable
called "year". If it does not exist, it will evaluate the next
alternative. Since it is a simple value (it's not an expression,
because it has not key – as marked by colon), it will be taken as is
and the complete expression will evaluate to "2010".
