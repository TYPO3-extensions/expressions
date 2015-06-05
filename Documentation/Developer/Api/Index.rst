.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer-api:

API
^^^

This section describes the API made available in the parser class
(:code:`\Cobweb\Expressions\ExpressionParser`).

All the methods are static, so there's no need to create an
instance of the parser class. To evaluate a string, simply make a call
like:

.. code:: php

	$parsedString = \Cobweb\Expressions\ExpressionParser::evaluateString($string, TRUE);


- `evaluateExpression`_
- `evaluateString`_
- `setExtraData`_
- `setVars`_


.. _developer-api-evaluate-expression:

evaluateExpression
""""""""""""""""""

This method evaluates a single expression and returns the value.

Parameters
  $expression
    The string to parse and evaluate. This string is not
    expected to contain subexpressions. Use :code:`evaluateString()` instead.


.. _developer-api-evaluate-string:

evaluateString
""""""""""""""

This method parses a string and evaluates every expression that it
contains. Furthermore the resulting string itself can be evaluated as
an expression.

Parameters
  $string
    The string to parse. It may contain subexpressions, which
    will be parsed appropriately.

  $doEvaluation
    Boolean, true by default. If true the string itself is
    evaluated as an expression, after all subexpressions have been
    evaluated. If false, the string itself is not evaluated.


.. _developer-api-set-vars:

setVars
"""""""

This method can be used to store so-called :ref:`"internal" variables <developer-variables>` inside
a static member variable of the parser class (see below).

Parameters
  $vars
    An array of any size.

  $reset
    Boolean. If true, the existing "internal" variables will be
    overridden by the content of :code:`$data`. If false (which is the default),
    the content of $data is merged with existing "internal" variables.


.. _developer-api-set-extra-data:

setExtraData
""""""""""""

This method can be used to store so-called :ref:`"external" variables <developer-variables>` inside
a static member variable of the parser class (see below).

Parameters
  $data
    An array of any size.

  $reset
    Boolean. If true, the existing "external" variables will be
    overridden by the content of :code:`$data`. If false (which is the default),
    the content of $data is merged with existing "external" variables.
