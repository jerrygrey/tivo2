Set oWMP = CreateObject("WMPlayer.OCX.7")
Set cdroms = oWMP.cdromCollection

For i = 0 to cdroms.Count-1
	
	If cdroms.Item(i).driveSpecifier = WScript.Arguments.Item(0) Then
		cdroms.Item(i).Eject
	End if
	
next

oWMP.close
