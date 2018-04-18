init:
	docker swarm init || echo "Swarm already initialized"
	cd php; make; cd -
	docker network create -d overlay uw-internal-1 || echo "Network already initialized"