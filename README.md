Have you been creating dynamically generated forms?

Is it a trouble to get hold of the whole submitted datas, convert them in xml and save 
the most peculiar datas in your DB so you can easily perform querys on them?

fo(rm)R(ESTFUL)(x)ml -> foRml integrates jQuery with RESTFUL protocol and PHP functions to easily accomplish that.

WHAT TO DO:

- Write your html form in this format:

        form name="xmlRootTagName" data-detect="true"
    
            input name="myFirstElementTagName" data-base="value"/
            input name="mySecondElementTagName" data-base="value"/
        
            fieldset name="myFirstNestedElementsRootTagName"
        
                input name="myFirstNestedElementTagName"/
                input name="mySecondNestedElementTagName"/
        
            /fieldset
        
            input name="myListElement" data-base="count"/
            input name="myListElement"/
    
        /form
    
- Configure your DB editing the php/config.xml file with hostname, user, password / DB name, Table name

- Here you go! On submitting your form a new .xml file will be created in the xml/ directory and a row will be
  added to your DB with those datas you specified as important with the data-base="..." attribute where "value"
  store the input value, and "count" stores the count of same named input elements in the form.

====