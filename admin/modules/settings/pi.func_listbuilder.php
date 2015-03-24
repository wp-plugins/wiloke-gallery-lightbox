<?php


add_meta_box 
( 
    'pi_images',
    __( 'Image/Gallery', self::LANG ),
    array($this, 'pi_upload_images'),
    self::PIG_POST_TYPE,
    'normal',
    'default'
);

add_meta_box
( 
    'pi_video',
    __( 'Embed Vimeo/Youtube', self::LANG ),
    array($this, 'pi_embed_video'),
    self::PIG_POST_TYPE,
    'normal',
    'default'
);

add_meta_box
( 
    'pi_linkto',
    __( 'Project Link', self::LANG ),
    array($this, 'pi_link_to'),
    self::PIG_POST_TYPE,
    'normal',
    'default'
);

add_meta_box
( 
    'pi_googlemap',
    __( 'Google Map', self::LANG ),
    array($this, 'pi_googlemap'),
    self::PIG_POST_TYPE,
    'normal',
    'default'
);


add_meta_box
( 
    'pi_popup_max_width',
    __( 'Maximum Value for lightbox width - unit px', self::LANG ),
    array($this, 'pi_popup_width'),
    self::PIG_POST_TYPE,
    'normal',
    'default'
);

add_meta_box
( 
    'pi_project',
    __( 'Project', self::LANG ),
    array($this, 'pi_project'),
    self::PIG_POST_TYPE,
    'normal',
    'default'
);
