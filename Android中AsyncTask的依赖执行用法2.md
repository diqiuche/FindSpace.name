#Pre 
在很久之前写过一篇[Android中AsyncTask的依赖执行用法][0]，说得比较乱，也没有用代码来明确说明，最近改coolhosts的代码时，发现这个写法真的太好用了，在添加功能和删除功能的时候，代码改动很少。
这里再利用[CoolHosts的代码][1](主要看coolhosts.java即可)详细解释一下。
#功能需求
多个多线程任务之间有依赖关系，用线程等待太丑陋，若采用线程结束自动调用下一个任务则可能在功能修改的时候任务量巨大。
#任务集合
比如coolhosts中：
```
private enum TASK
	{
		DOWNHOSTS,COPYNEWHOSTS,DELETEOLDHOSTS,GETURL,GETCLVERSION,GETHOSTSVERSION
	}
```
主要功能就是下载hosts，复制新hosts，删除原来的hosts，获取服务器上hosts的版本号等。
#队列的定义
```
private Queue <TASK> taskQueue=null;
...
public void onCreate(Bundle savedInstanceState) {  
...
    taskQueue = new LinkedList<TASK>();
...
```
新建任务队列，然后当需要任务的时候就添加任务：
```
        taskQueue.add(TASK.GETHOSTSVERSION);
        taskQueue.add(TASK.GETURL);
        taskQueue.add(TASK.GETCLVERSION);
```
#队列执行
队列可以通过`doNextTask()`来启动执行，而doNexTask函数就比较好玩了：
```
	public void doNextTask(){
		if(taskQueue!=null && taskQueue.peek()!=null){
			switch(taskQueue.remove()){
			case COPYNEWHOSTS:
				new FileCopier(CoolHosts.this).execute(CACHEDIR + "/hosts", "/system/etc/hosts");
				break;
			case DELETEOLDHOSTS:
				new FileCopier(CoolHosts.this).execute(null, "/system/etc/hosts");
				break;
			case DOWNHOSTS:
				downloadHostsTask.execute(Lib.SOURCE,Lib.HOSTSINCACHE);
				break;
			...
			}
		}
	}
```
每个case就是各自不同的任务，现在还一个问题，依赖还没有解决，现在只是执行一次，没有达到我们自动执行的要求，
#自动执行下一个任务
由于都是多线程任务，且均采用了AsyncTask，则可在任务结束自动进入`onPostExecute`方法的时候，调用`doNextTask();`即可。因为doNextTask()定义在了CoolHosts.java的主类里，所以在需要在任务类AsyncTask构造函数里传入caller。
例如webdownloader.java中：
```
public class WebDownloader extends AsyncTask<String, Void, File>{
	CoolHosts caller;
	public WebDownloader(CoolHosts caller) {
		this.caller=caller;
	}
	@Override
	protected File doInBackground(String... params) {
	...
	}
	@Override
    public void onPostExecute(File f) {
        if (f != null) {
			Log.d(CoolHosts.TAG, "download success");
			caller.doNextTask();
        }
    }
}
```
则在webdownloader结束的时候，自动调用doNextTask,若任务队列中仍有任务，则会自动执行，直到任务队列为空。
任务的添加，在一些事件触发（按钮被点击等）中使用`taskQueue.add(...)`即可。


[0]: http://www.findspace.name/easycoding/983
[1]: https://github.com/FindHao/CoolHosts 