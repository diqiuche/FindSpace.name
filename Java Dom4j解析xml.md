#Java dom4j解析xml文档

##dom4j项目地址：

[dom4j sourceforge][1]


##示例代码

```
InputSource in;
	SAXReader reader;
	Document doc;
	Element root,tempElement;
	WeatherInfo weather;
	/**内层元素遍历*/
	public ResolveXML(String filename) {
		//initialize
		in=new InputSource(filename);
		reader=new SAXReader();
		reader.setEncoding("utf-8");
		weather=new WeatherInfo();
		
		
		try {
			doc=reader.read(in);
			//根
			root=doc.getRootElement();
//			System.out.println(root.getName());
			//第一层
			Iterator<Element> item1=root.elementIterator();
			int i=0;
			while(item1.hasNext()){
				tempElement=(Element)item1.next();
				switch (i) {
				case 0:weather.setCity(tempElement.getText());					break;
				case 1:weather.setUpdateTime(tempElement.getText());break;
				case 2:weather.setTemperature(Integer.parseInt(tempElement.getText()));break;
				case 3:weather.setWindForce(tempElement.getText());break;
				case 4:weather.setHumidity(Integer.parseInt(tempElement.getText().substring(0, tempElement.getText().length()-1)));break;
				case 5:weather.setWindDirection(tempElement.getText());break;
				case 10:setEnvironment(tempElement.elementIterator(), weather);break;
				case 12:break;
				case 13:break;
				default:
					break;
				}
				
				
				
				System.out.println("The layer1's son:"+tempElement.getName()+"\t num:"+i);
				i++;
				
				
				
			}
			
			
			
		} catch (DocumentException e) {
			System.out.println("Here's error in reading xml.");
//			e.printStackTrace();
		}
	}
```

[1]: http://sourceforge.net/projects/dom4j/?source=navbar "dom4j"