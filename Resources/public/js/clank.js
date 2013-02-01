var Clank = (function()
{
    var uri = "";
    var session = false;

    call = function call(call, payload)
    {
        if (!this.session)
            throw new Error("Client must be connected to RPC");

        this.session.call(call, payload);

        return this;
    },


    subscribe = function(topic, callback)
    {
        if (!this.session)
            throw new Error("Client must be connected to subscribe to a topic.");

        this.session.subscribe(topic, callback);

        return this;
    },

    unsubscribe = function(topic)
    {
        if (!this.session)
            throw new Error("Client must be connected to unsubscribe to a topic.");

        this.session.unsubscribe(topic);

        return this;
    },

    publish = function(topic, payload)
    {
        if (!this.session)
            throw new Error("Client must be connected to publish to a topic.");

        this.session.publish(topic, payload);

        return this;
    },

    on = function(type, listener){
        if (typeof this._listeners[type] == "undefined"){
            this._listeners[type] = [];
        }

        this._listeners[type].push(listener);

        return this;
    },

    off = function(type, listener){
        if (this._listeners[type] instanceof Array){
            var listeners = this._listeners[type];
            for (var i=0, len=listeners.length; i < len; i++){
                if (listeners[i] === listener){
                    listeners.splice(i, 1);
                    break;
                }
            }
        }

        return this;
    },

    fire = function(event){
        if (typeof event == "string"){
            event = { type: event };
        }
        if (!event.target){
            event.target = this;
        }

        if (!event.type){  //falsy
            throw new Error("Event object missing 'type' property.");
        }

        if (this._listeners[event.type] instanceof Array){
            var listeners = this._listeners[event.type];
            for (var i=0, len=listeners.length; i < len; i++){
                listeners[i].call(this, event.data);
            }
        }

        return this;
    };

    return {
        connect: function(uri)
        {
            //check to allow uri specified with or without protocol
            uri = "ws://" + uri.replace("ws://", "");

            if (typeof ab === "undefined")
            {
                throw new Error("AutobahnJS must be included. Try adding http://autobahn.s3.amazonaws.com/js/autobahn.min.js");
                return;
            }

            var that = this;
            ab.connect(uri,
                //function for connection established.
                function (sess)
                {
                    session = sess;
                    that.fire("connected");

                },

                //function for error has occured / disconnected
                function (code, reason)
                {
                    that.session = false;
                    that.fire({type:"disconnected", data: {"code": code, "reason": reason}});
                }
            )

            return new Clank();
        }
    }
})();

/*
Clank.prototype.

Clank.prototype.

Clank.prototype.

Clank.prototype.

Clank.prototype.

Clank.prototype.

Clank.prototype.

Clank.prototype.*/