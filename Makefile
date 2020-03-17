init:
	docker swarm init || echo "Swarm already initialized"
	cd php; make; cd -
	docker network create -d overlay --attachable uw-internal-1 || echo "Network already initialized"
