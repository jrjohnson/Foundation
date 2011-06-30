function Form(){
  var self = this;
  
  this.create = function(obj){
    var div = $('<div>');
    div.addClass('form');
    div.append($('<p>').addClass('required').html('indicates a required field'));
    var form = $('<form>');
    $(obj.attributes).each(function(i){
      form.attr(this.name, this.value);
    });
    $(obj.fields).each(function(i){
      form.append(self.createField(this));
    });
    div.append(form);
    return div;
  }
  
  this.createField = function(obj){
    var field = $('<fieldset>');
    if(obj.legend){
      field.append($('<legend>').html(obj.legend));
    }
    if(obj.instructions){
      field.append($('<p>').addClass('instructions').html(obj.instructions));
    }
    $(obj.attributes).each(function(i){
      field.attr(this.name, this.value);
    });
    $(obj.elements).each(function(i){
      field.append(self.createElement(this));
    });
    return field;
  }
  
  this.createElement = function(obj){
    var div = $('<div>');
    div.addClass('field');
    div.addClass(obj.class);
    if(obj.required){
      div.addClass('required');
    }
    if(obj.instructions){
      div.append($('<p>').addClass('instructions').html(obj.instructions));
    }
    $(obj.messages).each(function(i){
      div.append($('<p>').addClass('message').html(String(this)));
    });
    var eDiv = $('<div>').addClass('element yui-gf');
    var lDiv = $('<div>').addClass('yui-u first label');
    if(obj.label){
      lDiv.append($('<label>').attr('for', obj.name).html(obj.label + ':'));
    }
    eDiv.append(lDiv);
    var cDiv = $('<div>').addClass('yui-u control');
    $(obj.views).each(function(i){
      var value = String(this);
      if(typeof(window[value]) == 'function'){
        cDiv.append(window[value](obj));
        return false; //break the loop
      }
    });
    eDiv.append(cDiv);
    div.append(eDiv);
    if(obj.format){
      div.append($('<p>').addClass('format').html(obj.format));
    }
    return div;
  }

}

function Input(obj){
  var input = $('<input>');
  input.attr('value', obj.value);
  input.attr('name', obj.name);
  $(obj.attributes).each(function(i){
    input.attr(this.name, this.value);
  });
  return input;
}

function TextInput(obj){
  return Input(obj);
}

function Plaintext(obj){
  return $('<p>').html(obj.value);
}

function Textarea(obj){
  var textarea = $('<textarea>');
  textarea.attr('name', obj.name);
  $(obj.attributes).each(function(i){
    textarea.attr(this.name, this.value);
  });
  textarea.html(obj.value);
  return textarea;
}

function CheckboxList(obj){
  var ol = $('<ol>');
  $(obj.items).each(function(i){
    var input = $('<input>').attr('type', 'checkbox').attr('name', obj.name).attr('id', obj.name + '_' + i);
    $(this.attributes).each(function(i){
      input.attr(this.name, this.value);
    });
    if(this.value == obj.value){
      input.attr('checked', true);
    }
    var li = $('<li>');
    li.append(input);
    li.append($('<label>').attr('for', obj.name + '_' + i).html(this.label));
    ol.append(li);
  });
  return ol;
}

function RadioList(obj){
  var ol = $('<ol>');
  $(obj.items).each(function(i){
    var input = $('<input>').attr('type', 'radio').attr('name', obj.name).attr('id', obj.name + '_' + i);
    $(this.attributes).each(function(i){
      input.attr(this.name, this.value);
    });
    if(this.value == obj.value){
      input.attr('checked', true);
    }
    var li = $('<li>');
    li.append(input);
    li.append($('<label>').attr('for', obj.name + '_' + i).html(this.label));
    ol.append(li);
  });
  return ol;
}

function SelectList(obj){
  var select = $('<select>');
  $(obj.attributes).each(function(i){
    select.attr(this.name, this.value);
  });
  
  $(obj.items).each(function(i){
    var option = $('<option>').html(this.label);
    $(this.attributes).each(function(i){
      option.attr(this.name, this.value);
    });
    if(this.value == obj.value){
      option.attr('selected', true);
    }
    select.append(option);
  });
  return select;
}

/**
 * Create a form object
 * Should mirror the API for Foundation_Form
 */
function FormObject(){
  var self = this;
  this.attributes = {};
  this.fields = [];
  
  this.setAttr = function(name,value){
    this.attributes.push({name: name, value: value});
  }
  
  this.newField = function(obj){
    var field = new FieldObject;
    $.each(obj, function(name, value){
      field.name = value;
    });
    this.fields.push(field);
    return field;
  }
  
}

function FieldObject(){
  var self = this;
  this.elements = [];
  this.legend = '';
  
  this.newElement = function(type, name){
    var element = new ElementObject(type,name);
    this.elements.push(element);
    return element;
  }
}

function ElementObject(type, name){
  var self = this;
  this.type = type;
  this.name = name;
  //elemnts ariving from PHP use an array of Class Names to select a view we just use the type sent and call it a day
  this.views = [type];
  this.value;
  this.label;
  this.format;
  this.instructions;
  this.required = false;
  this.defaultValue;
  this.attributes = [];
  this.items = [];
  
  this.addItem = function(label, value){
    var item = {
      label: label,
      value: value,
      attributes: {name: 'value', value: value}
    };
    this.items.push(item);
  }
}