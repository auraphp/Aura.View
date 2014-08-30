First stable 2.0 release.

- DOC: Update docblocks and README.

- CHG: View::render() now takes a second param, $data, for an array of vars to be extract()ed into the template scope. Closure-based templates will need to extract this on their own. (The previous technique of placing partial vars in the main template object still works.)
