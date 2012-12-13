package
{
	import flash.display.Sprite;
	
	public class TimeStampMain extends Sprite
	{
		public function TimeStampMain()
		{
			var totalMin:int = 760;
			var h		:int = totalMin/ 60;
			var m		:int = totalMin % 60;
			var d		:int = h / 24;
			h-=d*24;
			
			
			trace(d,'d', h,'h', m,'m' );
			
		}
	}
}