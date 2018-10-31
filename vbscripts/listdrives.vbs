Set oWMP = CreateObject("WMPlayer.OCX.7")
Set cdroms = oWMP.cdromCollection

for i = 0 to cdroms.Count-1
	WScript.Echo cdroms.Item(i).driveSpecifier
next

oWMP.close
