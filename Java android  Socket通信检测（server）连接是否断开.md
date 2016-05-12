[TOC]

#Pre
在利用socket写通讯程序的时候，想检测服务器是否还活着。
从网上找了很多资料，都没有自己合适的，最后自己想了个办法，不过也相当于截取了心跳检测的一部分。
这里检测的是远程server的连接，而不是本地是否连接成功。首先想到socket类的方法`isClosed()、isConnected()、isInputStreamShutdown()、isOutputStreamShutdown()`等，但经过试验并查看相关文档，这些方法都是本地端的状态，无法判断远端是否已经断开连接。
而有一个方法`sendUrgentData`，查看文档后得知它会往输出流发送一个字节的数据，只要对方Socket的SO_OOBINLINE属性没有打开，就会自动舍弃这个字节。
或者，其实如果断开连接了，你发送过去的数据会收不到，最后client会抛出io异常。
但是，上面两个方法，抛出异常的时间长达15秒！简直不能忍。
#解决思路
当然，这里的需求环境是：发送数据次数非常少，几乎只需要判断一两次，数据是集中发送的。那么，就可以这样了：
>只要client在发送数据前，先发送自定义的一个测试数据，并自定义一个String之类的变量（初始值null）来接收server发回的数据，client发完测试数据，睡500毫秒（自定义时间）（异步接收服务器消息），然后立刻检测这个string是不是null，就可以知道server是否收到消息了。

#代码

##客户端app上的部分代码
```

/**客户端线程，用来建立连接和发送消息 */
	public class Client implements Runnable{
		Socket s=null;
		DataInputStream dis=null;
		DataOutputStream dos=null;
		
		private boolean isConnected=false;
		Thread receiveMessage=null;
		/**发送消息
		 * @param str 发送的信息，client仅向server发送两次消息,这是我自己的应用需求，根据实际情况来做你的。
		 * @throws IOException 
		 * */
		public void sendMessage(String str) throws IOException{
				dos.writeUTF(str);
				dos.flush();
		}

		/**断开连接*/
		public void disConnect(){
			try {
				dos.close();
				dis.close();
				s.close();
			} catch (IOException e) {
				System.out.println("client closed error");
				e.printStackTrace();
			}
		}
		/**建立socket连接，开启接收数据线程		 * */
		public void run() {
			try {
				s=new Socket(SERVER_HOST_IP,SERVER_HOST_PORT);
				s.setOOBInline(true);
				dis=new DataInputStream(s.getInputStream());
				dos=new DataOutputStream(s.getOutputStream());
				System.out.println("connected!");
				isConnected=true;
				receiveMessage=new Thread(new ReceiveListenerThread());
				receiveMessage.start();
				//发送imei
				sendMessage(IMEI);
			} catch (UnknownHostException e) {
				System.out.println("fuwuqoweikaiqi");
				e.printStackTrace();
			} catch (IOException e) {
				System.out.println("ioerr");
				e.printStackTrace();
			}
		}

		private class ReceiveListenerThread implements Runnable{
		//这一部分接收数据的处理，请根据实际情况修改
			String data[]=new String[3];
			public void run() {
					try {
						if(isConnected){
							String receivedMessage=dis.readUTF();
							System.out.println(receivedMessage);
							serverStarted=true;
							data=receivedMessage.split("_");
							isLegal=Integer.parseInt(data[0]);
							num1=Integer.parseInt(data[1]);
							num2=Integer.parseInt(data[2]);
							System.out.println(""+isLegal+num1+""+num2);
						}
						if(isConnected){
							finalOK=dis.readUTF();
						}
					}catch (SocketException e){
						System.out.println("exit!");
					} catch (IOException e) {
						e.printStackTrace();
					}
			}
			
		}
```
##调用：
```
Client client=null;
/**客户端网络线程*/
Thread tClient=null;
client=new Client();
tClient=new Thread(client);
tClient.start();
```
##服务器上：
```

/** 这是服务器用来接收处理客户端的线程类 */
	class Client implements Runnable {
		private Socket s;
		private DataInputStream dis = null;
		private DataOutputStream dos = null;
		private boolean isConnected = false;
		public Client(Socket s) {
			this.s = s;
			try {
				dis = new DataInputStream(s.getInputStream());
				dos = new DataOutputStream(s.getOutputStream());
				isConnected = true;
			} catch (IOException e) {
				System.out.println("on clients' data in and out have error");
				// e.printStackTrace();
			}
		}
		public void run() {
			String str;
			try {
				// 先检查是否是合法的客户端
				while (isConnected) {
					str = dis.readUTF();
						str = dis.readUTF();
						//此处的具体代码省略
						dos.writeUTF("ok");
						dos.flush();
					}
				}
			} catch (EOFException e) {
				System.out.println("Client closed");
				Home.appendMessage((legalNum + 1) + "号客户端已经登出");
			} catch (IOException e) {
				e.printStackTrace();
			} finally {
				try {
					if (dis != null)
						dis.close();
					if (dos != null)
						dos.close();
					if (s != null)
						s.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
		}
	}
```

##客户端判断服务器是否还活着代码：

```
try {
	//客户端发送一个测试信息
		client.sendMessage(cookie);
		System.out.println("send");
		//睡1秒
		SystemClock.sleep(1000);
		//这个finalok是在客户端的接收线程那里处理的。如果不是null说明服务器没问题
		if(finalOK!=null){
			client.disConnect();
			exit("感谢您的使用，本次已经结束~");	
		}else{
			throw new  IOException() ;
		}
		//超时检测
	} catch (IOException e) {
		new AlertDialog.Builder(MainActivity.this).setTitle("提示").setPositiveButton("好的", null).setMessage("服务器关闭了，请联系管理员并在服务器重新开启后再次点击【发送】来提交数据").show();
		client.disConnect();
		e.printStackTrace();
	}
```
#后记：
当初只是简单记录了下自己的想法，写的很简陋，没想到慢慢这篇文章还好多人看，深感不安，故更新补充一下。
2015.3.27