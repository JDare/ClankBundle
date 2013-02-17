#Share config between server and client

Its useful to keep the server configuration isolated from the application. Here is a trivial way to share the Clank Config between the server and the client.

###Step 1: Add the connection details to parameters.yml

In your "app/config/parameters.yml"

```yml
    ...
    clank_host:    127.0.0.1
    clank_port:    8080

```

###Step 2: Replace the config.yml with the parameters

Open "app/config/config.yml" and change the following:

```yaml
clank:
  web_socket_server:
      port: 8080
      host: 127.0.0.1
```

to

```yaml
clank:
  web_socket_server:
      port: "%clank_port%"
      host: "%clank_host%"
```

###Step 3: Add to twig for client side access

So the client side templating can access those same parameters, add this to your "app/config/config.yml"

```yaml
twig:
    ...
    globals:
        clank_host:    "%clank_host%"
        clank_port:    "%clank_port%"
```

###Step 4: Render in template

In your root twig layout template, add the following

```html
<script type="text/javascript">
    var _CLANK_URI = "ws://{{ clank_host }}:{{ clank_port }}";
</script>
```

Now you will have access to a variable "_CLANK_URI" which you can connect with:

```javascript
var myClank = Clank.connect(_CLANK_URI);
```

Alternatively, if you don't like polluting your global scope, you can render it directly into your javascript file by processing it via a controller.
