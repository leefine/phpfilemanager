function newfolder(p) {
    var n = document.getElementById("newfilename").value;
    if (n !== null && n !== '') {
        window.location.hash = "#";
        window.location.search = 'p=' + encodeURIComponent(p) + '&new=' + encodeURIComponent(n);
    }
}
function rename(p, f) {
    var n = prompt('New name', f);
    if (n !== null && n !== '' && n != f) {
        window.location.search = 'p=' + encodeURIComponent(p) + '&ren=' + encodeURIComponent(f) + '&to=' + encodeURIComponent(n);
    }
}
function change_checkboxes(l, v) {
    for (var i = l.length - 1; i >= 0; i--) {
        l[i].checked = (typeof v === 'boolean') ? v: !l[i].checked;
    }
}
function get_checkboxes() {
    var i = document.getElementsByName('file[]'),
    a = [];
    for (var j = i.length - 1; j >= 0; j--) {
        if (i[j].type = 'checkbox') {
            a.push(i[j]);
        }
    }
    return a;
}
function checkbox_toggle() {
    var l = get_checkboxes();
    l.push(this);
    change_checkboxes(l);
}
function showSearch(u) {
  if(window.searchObj===undefined){
    var http = new XMLHttpRequest();
    var params = "path=" + u + "&type=search&ajax=true";
    http.open("POST", '', true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            window.searchObj = http.responseText;
            document.getElementById('searchresultWrapper').innerHTML = "";
            window.location.hash = "#searchResult"
        }
    }
    http.send(params);
  }else{
    document.querySelector('input[type=search]').value="";
    document.getElementById('searchresultWrapper').innerHTML = "";
    window.location.hash = "#searchResult"
  }
}
var searchEl = document.querySelector('input[type=search]');
var timeout = null;
var folders = [],files = [];
if (searchEl != null) searchEl.onkeyup = function(evt) {
    clearTimeout(timeout);
    if(window.searchObj===undefined)return;
    var data = JSON.parse(window.searchObj);
    var searchTerms = document.querySelector('input[type=search]').value.toLowerCase();
    timeout = setTimeout(function() {
        if (searchTerms.length >= 1) {
            folders = [];
            files = [];
            getSearchResult(data, searchTerms);
            var res = {folders:folders,files:files};
            var f1 = '',f2 = '';
            res.folders.forEach(function(d) {
                f1 += '<li class="' + d.type + '"><a target="_blank" href="?p=' + d.path + '">' + d.name + '</a></li>';
            });
            res.files.forEach(function(d) {
                f2 += '<li class="' + d.type + '"><a target="_blank" href="?p=' + d.path + '&view=' + d.name + '">' + d.name + '</a></li>';
            });
            document.getElementById('searchresultWrapper').innerHTML = '<div class="model-wrapper">' + f1 + f2 + '</div>';
        }
        else{ document.getElementById('searchresultWrapper').innerHTML = ""; }
    },
    500);
};
function getSearchResult(data, searchTerms) {
    for(var d in data)
    {
        if (data[d].type === 'folder') {
            getSearchResult(data[d].items, searchTerms);
            if (data[d].name.toLowerCase().match(searchTerms)) {
                folders.push(data[d]);
            }
        } else if (data[d].type === 'file') {
            if (data[d].name.toLowerCase().match(searchTerms)) {
                files.push(data[d]);
            }
        }
    }
}


//dtree



// Node object
function Node(id, pid, cname, cvalue, cshow, cchecked, cdisabled, url, title, target, icon, iconOpen, open) {
	this.id = id;
	this.pid = pid;
	//chechbox的名称
	this.cname = cname;
	//chechbox的值
	this.cvalue = cvalue;
	//chechbox的显示
	this.cshow = cshow;
	//chechbox是否被选中，默认是不选
	this.cchecked = cchecked||false;
	//chechbox是否可用，默认是可用
	this.cdisabled = cdisabled||false;
	//节点链接，默认是虚链接
	this.url = url||'#';
	this.title = title;
	this.target = target;
	this.icon = icon;
	this.iconOpen = iconOpen;
	this._io = open || false;
	this._is = false;
	this._ls = false;
	this._hc = false;
	this._ai = 0;
	this._p;
};

// Tree object
function dTree(objName) {
	this.config = {
		target		: null,
		folderLinks	: false,
		useSelection	: false,
		useLines	: true,
		useStatusText	: true
	}
	this.icon = {
		empty		: 'index.php?getico=empty',
		line		: 'index.php?getico=line',
		join		: 'index.php?getico=join',
		joinBottom	: 'index.php?getico=joinbottom',
		plus		: 'index.php?getico=plus',
		plusBottom	: 'index.php?getico=plusbottom',
		minus		: 'index.php?getico=minus',
		minusBottom	: 'index.php?getico=minusbottom',
		nlPlus		: 'index.php?getico=nolines_plus',
		nlMinus		: 'index.php?getico=nolines_minus'
	};
	this.obj = objName;
	this.aNodes = [];
	this.aIndent = [];
	this.root = new Node(-1);
	this.selectedNode = null;
	this.selectedFound = false;
	this.completed = false;
};

// Adds a new node to the node array
dTree.prototype.add = function(id, pid, cname, cvalue, cshow, cchecked, cdisabled, url, title, target, icon, iconOpen, open) {
	this.aNodes[this.aNodes.length] = new Node(id, pid, cname, cvalue, cshow, cchecked, cdisabled, url, title, target, icon, iconOpen, open);
};

// Open/close all nodes
dTree.prototype.openAll = function() {
	this.oAll(false);
};
dTree.prototype.closeAll = function() {
	this.oAll(true);
};

// Outputs the tree to the page
dTree.prototype.toString = function() {
	var str = '<div class="dtree">\n';
	if (document.getElementById) {		
		str += this.addNode(this.root);
	} else str += 'Browser not supported.';
	str += '</div>';
	if (!this.selectedFound) this.selectedNode = null;
	this.completed = true;
	return str;
};

// Creates the tree structure
dTree.prototype.addNode = function(pNode) {
	var str = '';
	var n=0;
	if (this.config.inOrder) n = pNode._ai;
	for (n; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == pNode.id) {
			var cn = this.aNodes[n];
			cn._p = pNode;
			cn._ai = n;
			this.setCS(cn);
			if (!cn.target && this.config.target) cn.target = this.config.target;
			
			if (!this.config.folderLinks && cn._hc) cn.url = null;
			if (this.config.useSelection && cn.id == this.selectedNode && !this.selectedFound) {
					cn._is = true;
					this.selectedNode = n;
					this.selectedFound = true;
			}
			str += this.node(cn, n);
			if (cn._ls) break;
		}
	}
	return str;
};

// Creates the node icon, url and text
dTree.prototype.node = function(node, nodeId) {
	var str = '<div class="dTreeNode">' + this.indent(node, nodeId);
	
	if (node.url) {
		str += '<a id="s' + this.obj + nodeId + '" class="' + ((this.config.useSelection) ? ((node._is ? 'nodeSel' : 'node')) : 'node') + '" href="' + node.url + '"';
		if (node.title) str += ' title="' + node.title + '"';
		if (node.target) str += ' target="' + node.target + '"';
		if (this.config.useStatusText) str += ' onmouseover="window.status=\'' + node.cname + '\';return true;" onmouseout="window.status=\'\';return true;" ';
		if (this.config.useSelection && ((node._hc && this.config.folderLinks) || !node._hc))
			str += ' onclick="javascript: ' + this.obj + '.s(' + nodeId + ');"';
		str += '>';
	}
	else if ((!this.config.folderLinks || !node.url) && node._hc && node.pid != this.root.id)
		str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');" class="node">';

	//str += node.name;
	if(node.pid == this.root.id){
		str += node.cname;
	}else{
		/**组装checkbox开始*/
		checkboxSyntax = "<input type='checkbox' desc='" + node.cshow + "' name='" + node.cname + "[]' id='" + node.cname + "_" + node.id + "' value='" + node.cvalue + "' onClick='javascript: " + this.obj + ".checkNode(" + node.id+","+node.pid+","+node._hc + ",this.checked);' ";
		//是否被选中
		if(node.cchecked)
			checkboxSyntax += " checked ";
		//是否可用
		if(node.cdisabled)
			checkboxSyntax += " disabled ";			
		checkboxSyntax += ">" + node.cshow;
		/**组装checkbox结束*/
				
		str += checkboxSyntax;
	}
		
	if (node.url || ((!this.config.folderLinks || !node.url) && node._hc)) str += '</a>';
	str += '</div>';
	if (node._hc) {
		str += '<div id="d' + this.obj + nodeId + '" class="clip" style="display:' + ((this.root.id == node.pid || node._io) ? 'block' : 'none') + ';">';
		str += this.addNode(node);
		str += '</div>';
	}
	this.aIndent.pop();
	return str;
};

// Adds the empty and line icons
dTree.prototype.indent = function(node, nodeId) {
	var str = '';
	if (this.root.id != node.pid) {
		for (var n=0; n<this.aIndent.length; n++)
			str += '<img src="' + ( (this.aIndent[n] == 1 && this.config.useLines) ? this.icon.line : this.icon.empty ) + '" alt="" />';
		(node._ls) ? this.aIndent.push(0) : this.aIndent.push(1);
		if (node._hc) {
			str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');"><img id="j' + this.obj + nodeId + '" src="';
			if (!this.config.useLines) str += (node._io) ? this.icon.nlMinus : this.icon.nlPlus;
			else str += ( (node._io) ? ((node._ls && this.config.useLines) ? this.icon.minusBottom : this.icon.minus) : ((node._ls && this.config.useLines) ? this.icon.plusBottom : this.icon.plus ) );
			str += '" alt="" /></a>';
		} else str += '<img src="' + ( (this.config.useLines) ? ((node._ls) ? this.icon.joinBottom : this.icon.join ) : this.icon.empty) + '" alt="" />';
	}
	return str;
};

// Checks if a node has any children and if it is the last sibling
dTree.prototype.setCS = function(node) {
	var lastId;
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == node.id) node._hc = true;
		if (this.aNodes[n].pid == node.pid) lastId = this.aNodes[n].id;
	}
	if (lastId==node.id) node._ls = true;
};


dTree.prototype.checkNode = function(id,pid,_hc,checked) {
	//1、递归选父节点对象（无论是叶节点还是中间节点）
	//判断同级中有无被选中的，如果有选中的就不可以反选
	if(!this.isHaveBNode(id,pid)){
		if(checked){
			//选中就一直选到根节点
			this.checkPNodeRecursion(pid,checked);
		}else{
			//去掉选中仅将其父节点去掉选中
			this.checkPNode(pid,checked);
		}
	}	
	
	//2、如果是中间结点，具有儿子，递归选子节点对象		
	if(_hc)		
		this.checkSNodeRecursion(id,checked);
	
}

dTree.prototype.isHaveBNode = function(id,pid) {	
	var isChecked = false
	for (var n=0; n<this.aNodes.length; n++) {
		// 不是节点自身、具有同父节点兄弟节点
		if (this.aNodes[n].pid!=-1&&this.aNodes[n].id!=id&&this.aNodes[n].pid == pid) {			
			if(eval("document.all."+ this.aNodes[n].cname + "_" + this.aNodes[n].id + ".checked"))
				isChecked = true;			
		}
	}
	
	return isChecked;
};

dTree.prototype.checkPNodeRecursion = function(pid,ischecked) {	
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid!=-1&&this.aNodes[n].id == pid) {			
			eval("document.all."+ this.aNodes[n].cname + "_" + this.aNodes[n].id + ".checked = " + ischecked);
			this.checkPNodeRecursion(this.aNodes[n].pid,ischecked);
			break;
		}
	}
};

dTree.prototype.checkSNodeRecursion = function(id,ischecked) {	
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid!=-1&&this.aNodes[n].pid == id) {			
			eval("document.all."+ this.aNodes[n].cname + "_" + this.aNodes[n].id + ".checked = " + ischecked);
			this.checkSNodeRecursion(this.aNodes[n].id,ischecked);			
		}
	}
};

dTree.prototype.checkPNode = function(pid,ischecked) {	
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid!=-1&&this.aNodes[n].id == pid) {			
			eval("document.all."+ this.aNodes[n].cname + "_" + this.aNodes[n].id + ".checked = " + ischecked);			
			break;
		}
	}
};

// Highlights the selected node
dTree.prototype.s = function(id) {
	if (!this.config.useSelection) return;
	var cn = this.aNodes[id];
	if (cn._hc && !this.config.folderLinks) return;
	if (this.selectedNode != id) {
		if (this.selectedNode || this.selectedNode==0) {
			eOld = document.getElementById("s" + this.obj + this.selectedNode);
			eOld.className = "node";
		}
		eNew = document.getElementById("s" + this.obj + id);
		eNew.className = "nodeSel";
		this.selectedNode = id;
		
	}
};

// Toggle Open or close
dTree.prototype.o = function(id) {
	var cn = this.aNodes[id];
	this.nodeStatus(!cn._io, id, cn._ls);
	cn._io = !cn._io;
	
};

// Open or close all nodes
dTree.prototype.oAll = function(status) {
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n]._hc && this.aNodes[n].pid != this.root.id) {
			this.nodeStatus(status, n, this.aNodes[n]._ls)
			this.aNodes[n]._io = status;
		}
	}
	
};

// Opens the tree to a specific node
dTree.prototype.openTo = function(nId, bSelect, bFirst) {
	if (!bFirst) {
		for (var n=0; n<this.aNodes.length; n++) {
			if (this.aNodes[n].id == nId) {
				nId=n;
				break;
			}
		}
	}
	var cn=this.aNodes[nId];
	if (cn.pid==this.root.id || !cn._p) return;
	cn._io = true;
	cn._is = bSelect;
	if (this.completed && cn._hc) this.nodeStatus(true, cn._ai, cn._ls);
	if (this.completed && bSelect) this.s(cn._ai);
	else if (bSelect) this._sn=cn._ai;
	this.openTo(cn._p._ai, false, true);
};

// Closes all children of a node
dTree.prototype.closeAllChildren = function(node) {
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == node.id && this.aNodes[n]._hc) {
			if (this.aNodes[n]._io) this.nodeStatus(false, n, this.aNodes[n]._ls);
			this.aNodes[n]._io = false;
			this.closeAllChildren(this.aNodes[n]);		
		}
	}
}

// Change the status of a node(open or closed)
dTree.prototype.nodeStatus = function(status, id, bottom) {
	eDiv	= document.getElementById('d' + this.obj + id);
	eJoin	= document.getElementById('j' + this.obj + id);
	
	eJoin.src = (this.config.useLines)?
	((status)?((bottom)?this.icon.minusBottom:this.icon.minus):((bottom)?this.icon.plusBottom:this.icon.plus)):
	((status)?this.icon.nlMinus:this.icon.nlPlus);
	eDiv.style.display = (status) ? 'block': 'none';
};



// If Push and pop is not implemented by the browser
if (!Array.prototype.push) {
	Array.prototype.push = function array_push() {
		for(var i=0;i<arguments.length;i++)
			this[this.length]=arguments[i];
		return this.length;
	}
};
if (!Array.prototype.pop) {
	Array.prototype.pop = function array_pop() {
		lastElement = this[this.length-1];
		this.length = Math.max(this.length-1,0);
		return lastElement;
	}
};