# SEAP_editor
PHP based JSON editor for SEAP content

master branch auto-deploys to http://seap-editor.herokuapp.com/

## To load new file contents to gh-pages staging app (e.g. http://neontribe.github.io/SEAP_ESA)

  - go to http://seap-editor.herokuapp.com/files/ESA-assesment-data.json
  - select all contents of this text file
  - replace contents of file at https://github.com/neontribe/SEAP_ESA/tree/master/src/assessment-data.json with the selected text
  - commit new file to the repo (in any branch) - as long as tests pass, it will auto deply to gh-pages staging
