NordCast
========
Welcome to NordCast Ltd. - a Germany-based broadcasting service. in this repository you will find all technical artifacts to realize the architecture proposed in our solution proposal to provide a global video streaming service.

Artifacts
----
* [Architectural diagram](architecture.png)
* [ARM templates](arm)
* [Frontend Docker container image](frontend)

Demo
----
A functioning demo is available at: http://nordcast.westeurope.cloudapp.azure.com/ (no https!)

Improvements
----
This solution is based on a very simple MVP (minimum viable product) and works as a proof on concept. In case of production like implementations, there is lots of space for improvement:
* Real 3-Tier architecture: Currently, the logic is integrated into the frontend for reducing efforts. In real production scenarios, this would be split into frontend and backend
* Tether services to local Azure network: by using Service Endpoints, the public available services such as Storage Account and CosmosDB should be integrated into local Azure network to a higher level of security
* More distinct network segmentation: To increase security even more, a more disctinct network segmentation by using different security zones should be used
* Real global scalability: Currently, only the videos are streamed using a CDN to scale to a global level. The webfrontend is currently delivered using a single region (Azure West Europe). Web delivery could also be done using the CDN and CosmosDB could be used for global data replication

Contact
-------
Julian Joswig

julian.joswig@me.com

+49 171 2781 820