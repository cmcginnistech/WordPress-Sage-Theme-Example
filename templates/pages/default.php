<?php

use Roots\Sage\Extras;

// Show me the components!
if(!post_password_required()) :
  Extras\componify();
else :
  the_content();
endif;
