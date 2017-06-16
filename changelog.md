6/16/2017
- Improved empty part from Request::Required method (now accepts boolean false)

6/14/2017
- Request required method updated (now takes infinite number of arguments)
- Improved Request handling 

### To fix your code change this:
```
Request::required($_POST, ['id', 'name']);
```
to this:
```
Request::required('id', 'name');
```