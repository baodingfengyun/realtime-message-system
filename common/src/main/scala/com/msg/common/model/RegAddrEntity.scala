package com.msg.common.model

import java.util.HashSet
import scala.concurrent.forkjoin.ThreadLocalRandom
import java.util.ArrayList
import scala.collection.JavaConversions._

object RegAddrEntity {
	private val regionStore = new ArrayList[String]
	def addAddress(address: String) = {
		if (!regionStore.contains(address)) {
			regionStore.add(address)
		}
	}

	def mergeAddress(liveAddress: ArrayList[String]) = {
		var oldArr = new ArrayList[String]()
		for (oa: String <- regionStore) {
			oldArr.add(oa)
		}
		for (oldAddress: String <- oldArr) {
			if (liveAddress.size() == 0) {
				removeAddress(oldAddress)
			} else {
				if (!liveAddress.contains(oldAddress)) {
					removeAddress(oldAddress)
				}
			}
		}
	}

	def removeAddress(address: String) = {
		regionStore.remove(address)
	}
	def getRandomAddress(): String = {
		var address = ""
		try {
			address = regionStore.get(ThreadLocalRandom.current.nextInt(regionStore.size))
		} catch {
			case t: Throwable =>
		}
		if (address.isEmpty()) {
			try {
				address = regionStore.get(ThreadLocalRandom.current.nextInt(regionStore.size))
			} catch {
				case t: Throwable =>
			}
		}
		address
	}
	def getAllRegionAddress(): ArrayList[String] = {
		regionStore
	}
}
