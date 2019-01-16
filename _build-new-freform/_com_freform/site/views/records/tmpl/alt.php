<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

$count  = count($this->items);
$is_are = $count == 1 ? 'is' : 'are';
$s_no_s = $count == 1 ? '' : 's';
?>
<p>
There <?php echo $is_are;?> <b><?php echo $count; ?></b> item<?php echo $s_no_s; ?> in the database.
</p>

<p>
An alternate view of the data. This could be something like a map or chart instead of a table.
</p>

<p>
It doesn't need it's own view, so this 'page' only exists as an alternate layout.
</p>

<p>
It's only here to provide an example of how this sort of thing is done. If you don't need this, you can delete it. 
</p>

<p>
If you need more alternative views, you can create more by following this example. 
</p>

<p>
<a href="<?php echo JRoute::_('index.php?option=com__freform'); ?>">Back</a>
</p>