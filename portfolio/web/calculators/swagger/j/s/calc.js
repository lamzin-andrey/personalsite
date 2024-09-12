class SwaggerCalc {
    constructor(ctx) {
        this.bus = ctx;
    }
    
    process(method, url, json) {
        let o = this, response, props = [],
            propTpl = o.responseOnePropertyTpl(),
            respTpl = o.response200Tpl(),
            type, value, name, propText,
            offset = o.getOffset(respTpl)
        try {
            response = JSON.parse(json);
        } catch (err) {
            return err;
        }
        
        for (name in response) {
            value = response[name];
            type = o.parseType(value);
            propText = propTpl.replace("{{name}}", name);
            propText = propText.replace("{{type}}", type);
            propText = propText.replace("{{value}}", value);
            props.push(propText);
        }
        propText = props.join("\n", props);
        propText = o.setOffset(propText, offset);
        
        response = respTpl.replace("{{props}}", propText);
        let offset2 = '        ';
        return o.setOffset(response, offset2);
    }
    
    parseType(vl) {
		if (String(parseInt(vl)) === String(vl)) {
			return "integer";
		}
		if (String(parseFloat(vl)) === String(vl)) {
			return "number";
		}
		
		if (vl instanceof Array) {
			return "array";
		}
		
		if (vl instanceof Object) {
			return "object";
		}
		
		return "string";
	}
    
    
    setOffset(t, offset){
		let i, SZ, ls, line,
			n = '\n',
			r = [];
		ls = t.split(n);
		SZ = sz(ls);
		for (i = 0; i < SZ; i++) {
			line = ls[i];
			if (i > 0) {
				line = offset + line;
			}
			r.push(line);
		}
		
		return r.join(n);
	}
    
    getOffset(r){
		let i, SZ, ls, line,
			tk = "{{props}}";
		ls = r.split('\n');
		SZ = sz(ls);
		for (i = 0; i < SZ; i++) {
			line = ls[i];
			if (line.indexOf(tk) != -1) {
				return line.split(tk)[0];
			}
		}
		
		return "";
	}
    
    response200Tpl() {
        return `'200':
  description: TODO
  content:
    application/json:
      schema:
        type: object
        properties:
          {{props}}`;
    }
    
    responseOnePropertyTpl() {
        return `{{name}}:
  type: {{type}}
  example: {{value}}
  description: TODO`;
    }
}

