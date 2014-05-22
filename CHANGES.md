- [BRK] Stop using a "content variable" and begin using setContent()/getContent() instead.  In your layouts, replace `echo $this->content_var_name` with `echo $this->getContent()`. (This also removes the `setContentVar()` and `getContentVar()` methods.)

- [ADD] Add support for sections per strong desire from @harikt, which fixes #46.  The new methods are `setSection()`, `hasSection()`, and `getSection()`, along with `beginSection()` and `endSection()`.
