.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer-hooks:

Hooks
^^^^^

There's one hook available in the parser library. It allows the
manipulation of the value resulting from an expression. It must be
registered with something like:

.. code:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['expressions']['postprocessReturnValue'][] = 'Vendor\\MyExtension\\Hook\\MyExpressionHook';

It must implement the :code:`\Cobweb\Expressions\ValuePostProcessorInterface`
interface. Here's a sample code:

.. code:: php

	namespace Vendor\MyExtension\Hook;
	class MyExpressionHook implements \Cobweb\Expressions\ValuePostProcessorInterface {
		public function postprocessReturnValue($value) {
			if (is_numeric($value)) {
				$value += 30;
			}
			return $value;
		}
	}

This sample code will add 30 to the given value, if it is numeric.

As can be seen, the hook method receives the value itself and is
expected to return it, even if unchanged.
