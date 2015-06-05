.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer-variables:

Internal and external variables
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The idea behind internal and external variables is to provide two
different spaces where to store custom data, to be retrieved by the
parser. What is available in these spaces depends on what you stored
into them. This is really up to each extension that uses expressions.

The concept is that "vars" is for values that can be considered as
internal to the extension. For example, for a traditional FE plugin, you might
want to load the piVars into the "vars" array, so that they can be
used inside an expression. On the other hand, the "extra data" array
might contain values that come from some other external source, for
example some general TypoScript.

It is up to each extension developer to decide whether to use those
arrays or not, or also to differentiate between the two or just use a
single one. It is really just a conceptual difference, so it must
essentially make sense with regard to the extension itself.


.. _developer-variables-example:

Example
"""""""

.. code:: php

	$data = array(
		'foo' => 'bar'
	);
	\Cobweb\Expressions\ExpressionParser::setExtraData(
		$data,
		FALSE
	);

This will load the :code:`$data` array into the internal variables. Values
from this array can then be retrieved with the following expression:

.. code:: text

	extra:foo

which will return "bar".
