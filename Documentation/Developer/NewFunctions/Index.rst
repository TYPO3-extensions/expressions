.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer-new-functions:

Introducing new functions
^^^^^^^^^^^^^^^^^^^^^^^^^

Just like with keys, it is possible to use a hook to introduce custom
functions, which makes it possible to do pretty much whatever one
wants with expressions. An example class is provided in
:file:`Classes/Sample/FunctionProcessor.php` . It
introduces a function called "offset". To make this function
available, it must be registered as follows:

.. code:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['expressions']['customFunction']['offset'] = 'Cobweb\\Expressions\\Sample\\FunctionProcessor->offset';

The function itself looks like this:

.. code:: php

	public function offset($parameters, $parentObject) {
		$value = intval($parameters[0]);
		$offset = 0;
		if (isset($parameters[1])) {
			$offset = intval($parameters[1]);
		}
		return $value + $offset;
	}


It receives an array containing the function call parameters and what
would normally be a back-reference to the calling object, but is
actually a dummy object, since the :code:`\Cobweb\Expressions\ExpressionParser`
class is purely static. The call parameters array is an indexed array
containing:

- at index 0, the value that was calculated from the expression

- at index 1 and more, any additional parameters that were defined in
  the function call.

As an example, consider the following expression using this custom
function:

.. code:: text

   gp:foo->offset:5

The :code:`offset()` method will receive the value of "gp:foo" in
:code:`$parameters[0]` and "5" in :code:`$parameters[1]`.

The function is expected to return the modified value, or the original
value from the expression if no change happened.
