.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer-new-keys:

Introducing new keys
^^^^^^^^^^^^^^^^^^^^

Using hooks it is possible to create classes that can interpret new
keys. The first step is to create a class that implements the
:code:`\Cobweb\Expressions\KeyProcessorInterface` interface. This interface
defines a single method called :code:`getValue()` which is expected to return
some value after having interpreted the string it receives. What the
method receives is the part of the expression that comes after the
colon. For example, if the expression is:

.. code:: text

	key:value1|value2|value3...

the method will received "value1\|value2\|value3" as argument. The
method should handle that string and return whatever makes sense. It
may throw exceptions to show that the input was not valid and didn't
lead to a valid result.

If an exception is to be thrown, it is advised to throw a
:code:`\Cobweb\Expressions\Exception\Exception` or some
descendant of it.

The class must then be registered in your extension's :code:`ext\_localconf.php` file,
using the following syntax:

.. code:: php

	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['expressions']['keyProcessor'][key] = 'Vendor\\MyExtension\\Processor\\MyKeyProcessor';

.. important::

   This differs slightly from usual hooks. In this case the
   expression key that can be interpreted by the class is used as a key
   in the hook array. The advantage is
   that all registered hooks are not called unnecessarily every time a
   custom key is found. The limitation is that there can be only one
   registered hook per custom key, but it wouldn't make sense anyway to
   have several classes to handle the same custom key.


.. _developer-new-keys-example:

Example
"""""""

Assume the following expression must be parsed:

.. code:: text

   negative:yes

The class to handle it will have to be registered as:

.. code:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['expressions']['keyProcessor']['negative'] = 'Vendor\\MyExtension\\Processor\\MyKeyProcessor';

It will receive "yes" as an input. The code of the class may be
something like:

.. code:: php

	namespace Vendor\MyExtension\Processor;
	class MyKeyProcessor implements \Cobweb\Expressions\KeyProcessorInterface {
		public function getValue($indices) {
			$value = 'Yes';
			if ($indices == 'yes') {
				$value = 'No';
			}
			return $value;
		}
	}

In the above example, it will return "No".
