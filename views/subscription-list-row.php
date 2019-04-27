<?php
/* 
 * Subscription List Row
 */
?>
<tr><td><?= $first_name; ?></td><td><?= $email; ?></td><td><?= $subscribed; ?></td><td><?php if($subscribed === "Subscribed") { ?><a href="#" data-id="<?= $id ?>" class="unsubscribe">Unsubscribe</a><?php } else { ?>Unsubscribed<?php } ?></td></tr>