import sys
import urllib2
import json
import pprint

def main():
	
	# print 'total len og args ', 	len(sys.argv)
	# print 'First param', 		sys.argv[1]
	# print 'second param', 		sys.argv[2]
	# print 'third param', 		sys.argv[3]
	
	call_url 		= sys.argv[3]
	
	nodeToNodes 	= dict()
	response 		= urllib2.urlopen("http://"+sys.argv[3])
	
	#print 'url responce', response.read()
	
	nodeToNodes = json.load(response)   
	
	#nodeToNodes = dict()
	# nodeToNodes['A'] = ['B', 'E']
	# nodeToNodes['B'] = ['C', 'E', 'D', 'A']
	# nodeToNodes['C'] = ['B', 'D', 'E']
	# nodeToNodes['D'] = ['C', 'B', 'E']
	# nodeToNodes['E'] = ['A', 'B', 'D', 'C']
	
	print str(getAllSimplePaths(sys.argv[1], sys.argv[2], nodeToNodes))
 
#
# Return all distinct simple paths from "originNode" to "targetNode".
# We are given the graph in the form of a adjacency list "nodeToNodes".
#
def getAllSimplePaths(originNode, targetNode, nodeToNodes):
    return helpGetAllSimplePaths(targetNode,
                                 [originNode],
                                 set(originNode),
                                 nodeToNodes,
                                 list())
 
#
# Return all distinct simple paths ending at "targetNode", continuing
# from "currentPath". "usedNodes" is useful so we can quickly skip
# nodes we have already added to "currentPath". When a new solution path
# is found, append it to "answerPaths" and return it.
#    
def helpGetAllSimplePaths(targetNode,
                          currentPath,
                          usedNodes,
                          nodeToNodes,
                          answerPaths):
    lastNode = currentPath[len(currentPath) - 1]
    if lastNode == targetNode:
        answerPaths.append(list(currentPath))
    else:
        for neighbor in nodeToNodes[lastNode]:
            if neighbor not in usedNodes:
                currentPath.append(neighbor)
                usedNodes.add(neighbor)
                helpGetAllSimplePaths(targetNode,
                                      currentPath,
                                      usedNodes,
                                      nodeToNodes,
                                      answerPaths)
                usedNodes.remove(neighbor)
                currentPath.pop()
    return answerPaths
 
if __name__ == '__main__':
    main()
