All of the helpers assume you are using pre-escaped values. You
will need to escape params yourself before passing them in; use
htmlspecialchars() with ENT_QUOTES and 'UTF-8'. (Aura View does that for you
automatically.)