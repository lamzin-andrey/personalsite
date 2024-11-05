class SwaggerCalc {
    constructor(ctx) {
        this.bus = ctx;
    }
    
    
    
    processParameters(method, url, json) {
        let o = this, parameters, concreteParameters,
			props = [], propsText = "", capName,
			concreteProps = [], concretePropsText = "",
			result = {},
            propTpl = o.parameterAsRefOnePropertyTpl(),
            propsTpl = o.parametersAsRefsTpl(),
            concretePropTpl = o.parameterTpl(),
            concretePropsTpl = o.parametersTpl(),
            type, value, name, propText, concretePropText,
            offset = o.getOffset(concretePropTpl, "example: {{value}}");
        try {
            parameters = JSON.parse(json);
            concreteParameters = "";
            
        } catch (err) {
            //return {"props": err, "concreteProps": err};
        }
        if (!parameters) {
			parameters = o.processUrl(url);
		}
        
        for (name in parameters) {
            value = parameters[name];
            type = o.parseType(value);
            capName = TextFormat.capitalize(name);
            concretePropText = concretePropTpl.replace("{{CapName}}", capName);
            concretePropText = concretePropText.replace("{{name}}", name);
            concretePropText = concretePropText.replace("{{type}}", type);
            concretePropText = concretePropText.replace("{{value}}", value);
            concretePropText = concretePropText.replace("{{in}}", o.getPathFromMethod(url));
            concreteProps.push(concretePropText);
            
            propText = propTpl.replace("{{paramName}}", capName);
            props.push(propText);
            
        }
        concreteParameters = concreteProps.join("\n");
        concreteParameters = o.setOffset(concreteParameters, offset);
        concretePropsText = concretePropsTpl.replace("{{parameters}}", concreteParameters);
        let offset2 = '        ';
        concretePropsText =  o.setOffset(concretePropsText, offset2);
        result["concreteProps"] = concretePropsText;
        
        parameters = props.join("\n");
        offset = o.getOffset(propTpl, "- $ref: '#/components/parameters/{{paramName}}'")
        parameters = o.setOffset(parameters, offset);
        propsText = propsTpl.replace("{{propsAsRef}}", parameters);
        propsText = propsText.replace("{{method}}", method.toLowerCase());
        propsText = propsText.replace("{{url}}", url.split('?')[0]);
        result["props"] = propsText;
        
        return result;
    }
    
    processResponse200(method, url, json) {
        let o = this, response, props = [],
            propTpl = o.responseOnePropertyTpl(),
            respTpl = o.response200Tpl(),
            type, value, name, propText,
            offset = o.getOffset(respTpl, "{{props}}")
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
    
    getOffset(r, tk){
		let i, SZ, ls, line;
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
	
	processUrl(url) {
		let GET = HttpQueryString.parse(url), i, a, SZ, p, o = this;
		if (count(GET) > 0) {
			o.paramsPlace = "query";
			console.log(GET);
			return GET; // TODO check format
		}
		o.paramsPlace = "path";
		GET = {};
		a = url.split('/');
		SZ = sz(a);
		for (i = 0; i < SZ; i++) {
			p = a[i].trim();
			if (p && p.indexOf('{') == 0 && p.indexOf('}') == sz(p) - 1) {
				p = p.replace('{', '');
				p = p.replace('}', '');
				GET[p] = "TODO attention! Check type and all! (This in path!)";
			}
		}
		
		return GET;
	}
	
	getPathFromMethod(url) {
		let o = this;
		if (!o.paramsPlace) {
			o.processUrl(url);
		}
		return this.paramsPlace;
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
    
    parameterAsRefOnePropertyTpl() {
		return `        - $ref: '#/components/parameters/{{paramName}}'`;
	}
	
	parametersAsRefsTpl() {
		return `{{url}}:
    {{method}}:
      summary: TODO
      parameters:
        {{propsAsRef}}`;
	}
	
	parameterTpl() {
		return `{{CapName}}:
      name: {{name}}
      in: {{in}}
      required: true # TODO
      schema:
        type: {{type}}
        description: 'TODO'
        example: {{value}}`;
	}
	
	parametersTpl() {
		return `{{parameters}}`;
	}
}

