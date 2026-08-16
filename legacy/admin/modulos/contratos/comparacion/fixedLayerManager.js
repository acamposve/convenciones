/* LICENSE : Released under the GNU LESSER GENERAL PUBLIC LICENSE v2.1
	             which can be found at "www.gnu.org/copyleft/lesser.html".
	HISTORY    : 2006/03/12 v1.0 */

// C O N S T R U C T O R S ***************************************************

/*
	SUMMARY: Constructor for class FixedLayerManager.
	ARG id : This value should be the same as the instance name.
	EXAMPLE: var flm = new FixedLayerManager("flm");
*/
function FixedLayerManager(id)
{
	this.id = id; // This should be the same as the instance name.
	this.timeout = null; // The timeout resource used by the onScrollHandler.
	this.layers = new Array(); // The layers this instance manages.
}

// P U B L I C   M E T H O D S ***********************************************

/*
	SUMMARY: Shows, makes visible, all layers managed by this instance.
	RETURNS: None.
*/
FixedLayerManager.prototype.showLayers = function()
{
	for (i = 0; i < this.layers.length; i++)
		this.layers[i].domLayer.style.visibility = 'visible';
}

/*
	SUMMARY: Hides, makes hidden, all layers managed by this instance.
	RETURNS: None.
*/
FixedLayerManager.prototype.hideLayers = function()
{
	// Prevent any waiting timeouts from showing the layers.
	if (this.timeout != null)
	{
		clearTimeout(this.timeout);
		this.timeout = null;
	}

	for (i = 0; i < this.layers.length; i++)
		this.layers[i].domLayer.style.visibility = 'hidden';
}

/*
	SUMMARY: Repositions all layers managed by this instance so that they
	         behave like a true fixed layer.
	RETURNS: None.
*/
FixedLayerManager.prototype.repositionLayers = function()
{
	for (i = 0; i < this.layers.length; i++)
	{
		this.layers[i].domLayer.style.top = this.layers[i].originalTop
			+ document.body.scrollTop;

		this.layers[i].domLayer.style.left = this.layers[i].originalLeft
			+ document.body.scrollLeft;
	}
}

/*
	SUMMARY: Add layer(s) for this instance to manage.
	ARG    : This method accepts a variable number or arguments. It can accept
	         either a single array of layers (as objects or id strings) or a
	         variable number of layer arguments (as objects or id strings).
	RETURNS: None.
	THROWS : Will throw a general exception (creating an exception class in
	         this case would be overkill) if any of the layers don't exists.
	EXAMPLE: flm.addLayers(new Array(layer1, "layer2", layer3)); // Method 1.
	         flm.addLayers(layer1, "layer2", layer3); // Method 2.
	NOTES  : Be careful if you call this function before the document.onload
	         event occurs as your layer may not yet be constructed and
	         therefore your layer does not yet exists.
*/
FixedLayerManager.prototype.addLayers = function()
{
	if (arguments.length == 0)
		return;

	// Assign args depending on if we're passed an array or a set of arguments.
	var args = (arguments[0] instanceof Array) ? arguments[0] : arguments;
	for (var i = 0; i < args.length; i++)
	{
		var domLayer = (typeof(args[i]) == 'object') ? args[i]
			: document.getElementById(args[i]); // Is the argument a layer or id.

		if (domLayer == null)
		{
			throw ('ERROR: Argument #' + i + ' (' + args[i] + ') in call to '
				+ this.id + '.addLayers(' + args.join(', ')
				+ ') is not an object or valid element ID.');
		}

		// Save the layer's info: Dom node, and original top & left position.
		var layer = new Array();
		layer['domLayer'] = domLayer;
		layer['originalTop'] = domLayer.offsetTop;
		layer['originalLeft'] = domLayer.offsetLeft;
		this.layers.push(layer); // Add it to the list of layers to manage.
	}
}

/*
	SUMMARY: Initializes this instance to handle the onscroll event. Also, adds
	         any passed layer arguments to the list of managed layers.
	ARG    : See the addLayers() method.
	RETURNS: true on success, false otherwise.
	THROWS : See the addLayers() method.
	NOTES  : This method replaces any window.onscroll handlers with it's own.
*/
FixedLayerManager.prototype.init = function()
{
	if (navigator.userAgent.match(/msie/i) == null)
		return false;

	window.onscroll = new Function(this.id + '.onScrollHandler();');
	this.addLayers.apply(this, arguments);

	return true;
}

// P R O T E C T E D   M E T H O D S *****************************************

/*
	SUMMARY: Handles window.onscroll events.
	RETURNS: None.
	NOTES  : Edit or overide this method to get the desired visual effect.
*/
FixedLayerManager.prototype.onScrollHandler = function()
{
	if (this.timeout == null)
		this.hideLayers();
	else
	{
		// Reset timeout if onscroll occurs before previous handler executed.
		clearTimeout(this.timeout);
		this.timeout = null;
	}

	this.repositionLayers();
	this.timeout = setTimeout(this.id + '.showLayers();' + this.id
		+ '.timeout = null;', this.ONSCROLL_HANDLER_DELAY);
}

// C L A S S   C O N S T A N T S *********************************************

// Milliseconds, after scrolling stops, until layers reappear.
FixedLayerManager.prototype.ONSCROLL_HANDLER_DELAY = 200;

/* ================== */
flm = new FixedLayerManager('flm');

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
  flm.init('TweedledeeDiv', 'TweedledumDiv');
});
